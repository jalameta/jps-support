<?php

namespace Jalameta\Support\Bus;

use BadMethodCallException;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializableClosure;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class BaseJob.
 *
 * @property-read \Illuminate\Http\Request $request
 * @method onSuccess(\Closure $callback)
 */
abstract class BaseJob
{
    use InteractsWithQueue, Queueable, Dispatchable, ValidatesRequests, SerializesModels {
        restoreModel as parentRestoreModel;
    }

    const STATUS_IDLE = 'idle';
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCESS = 'success';
    const STATUS_RUNNING = 'running';

    /**
     * Status of the job.
     *
     * @var string
     */
    protected $status = self::STATUS_IDLE;

    /**
     * List of all registered callbacks.
     *
     * @var array
     */
    protected $callbacks = [];

    /**
     * Original input data.
     *
     * @var array
     */
    protected $inputs = [];

    /**
     * Job constructor.
     *
     * @param array $inputs
     */
    public function __construct(array $inputs = [])
    {
        /**
         * @var Request
         */
        $request = app('request');

        if (app()->runningInConsole()) {
            $request->replace([]);
        }

        $request->merge($inputs);

        $this->inputs = $request->all();
    }

    /**
     * Make dynamic call to the command.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'on')) {
            $event = substr($name, 2);

            return $this->registerCallback(Str::lower($event), $arguments[0]);
        }

        throw new BadMethodCallException("Invalid Method `$name`");
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name === 'request') {
            $request = app('request');
            $request->merge($this->inputs);

            return $request;
        }

        return;
    }

    /**
     * Restore the model from the model identifier instance.
     *
     * @param  \Illuminate\Contracts\Database\ModelIdentifier  $value
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function restoreModel($value)
    {
        try {
            return $this->parentRestoreModel($value);
        } catch (ModelNotFoundException $exception) {
            return new $value->class;
        }
    }

    /**
     * Handle incoming job.
     *
     * @return $this
     */
    public function handle()
    {
        event('job.running', $this);

        // Determine if this command has a boot method, which is convenient when developer
        // needs to modify any information on this command before actually running it.
        if (method_exists($this, 'boot')) {
            $this->boot();
        }

        $this->fireCallbacks('running');

        $outcome = $this->run();
        $this->status = ($outcome) ? self::STATUS_SUCCESS : self::STATUS_FAILED;

        // base on the outcome of the run method, let's run additional callbacks.
        $this->fireCallbacks($this->status);

        event('job.finished', $this);

        return $this;
    }

    /**
     * Run the actual command process.
     *
     * @return mixed
     */
    abstract public function run() : bool;

    /**
     * Immediately calling abort callbacks.
     *
     * @return void
     */
    public function abort()
    {
        $this->fireCallbacks('abort');
    }

    /**
     * Determine if job is succeeded.
     *
     * @return bool
     */
    public function success()
    {
        return ($this->status === self::STATUS_SUCCESS);
    }

    /*
     * Determine if job is Failed
     *
     * @return bool
     */
    public function failed()
    {
        return ($this->status === self::STATUS_FAILED);
    }

    /**
     * Register Callback to the callbacks array.
     *
     * @param          $event
     * @param \Closure $callback
     *
     * @return $this
     */
    public function registerCallback($event, \Closure $callback)
    {
        $this->callbacks[$event][] = new SerializableClosure($callback);

        return $this;
    }

    /**
     * Fire all callbacks registered on the callbacks array.
     *
     * @param string $event
     *
     * @return void
     */
    protected function fireCallbacks($event)
    {
        if (array_key_exists($event, $this->callbacks)) {
            foreach ($this->callbacks[$event] as $callback) {
                /*
                 * @var $callback SerializableClosure
                 */
                app()->call($callback->getClosure(), [$this]);
            }
        }
    }
}
