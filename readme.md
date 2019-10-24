## JPS  Support
JPS Support is a set of tools that will improve your application development productivity. This package has 3 focus tools consider Bus, Database and Caching. 

> Requirements : Laravel or Lumen >= 5.6

## Getting Started
### Installing
`composer require jalameta/jps-support`

### Features
#### 1. Bus
JPS Support Bus providing the class for Laravel Job. Our guide is separate business logic from the controller. However, separating business logic into a Job can ease your application development process, it reduce the time consume on writing duplicated code. We encourage our developers to think the abstraction of business logic. One business logic can be breakdown into several use cases that could be used again in another business logic. How to use :

All you need is extends the BaseJob and implement the method `run()` stubs, then handle the job inside the run method. The method needs a boolean return type for indicating the job is running successfully. 

##### Basic Usage

```
use App\Bus\BaseJob;

class MakePayment extends BaseJob
{
    /**
     * Run the actual command process.
     *
     * @return mixed
     */
    public function run()
    {
        $payment = new Payment();
        $payment->total = $this->request->input('total');
        $payment->method = $this->request->input('method');
        $payment->user()->associate(auth()->user());
        
        return $payment->save();
    }
}
```

From the example above, you can access the request directly from the `request` attribute. The request attribute is from the original request combined with the array input in a class constructor.

##### Register callback

```
use Jalameta\Support\Bus\BaseJob;

class MakePayment extends BaseJob
{
    /** 
    * @var Payment
    */
    public $payment;
    
    /**
     * Run the actual command process.
     *
     * @return mixed
     */
    public function run()
    {
        // Callbacks are running when the job 
        // running successfully, based on the return value of run() method
        $this->onSuccess(function () {
            dispatch_now(new GenerateInvoice($this->payment))
        });
    
        $this->payment = new Payment();
        $this->payment->total = $this->request->input('total');
        $this->payment->method = $this->request->input('method');
        $this->payment->user()->associate(auth()->user());
        
        return $this->payment->save();
    }
}
```

Available callbacks are `onIdle`, `onRunning`, `onSuccess`, and `onFailed` 

##### Aborting Job
Occasionally you need to cancel the job before it finishes. You can register the `onAbort` callback and invoke `$this->abort()` method

#### 2. Composite Key
It is a trait helper if you have a table with a composite key. Directly use `HasCompositeKeys` trait inside the Eloquent model. It reduces boilerplate when using a composite key for updating an Eloquent model. 

##### Basic Usage

``` use Illuminate\Database\Eloquent\Model;
use Jalameta\Support\Database\Eloquent\HasCompositeKeys;

class Reserve extends Model
{
    use HasCompositeKeys;
}
```

#### 3. UUID as primary key
	As the composite key, this is the trait helper too. This will set the UUID key automatically for you. Prequities you need is define the primary key of the table to the Eloquent model. Don't forget to use the `UuidAsPrimaryKey` trait into your Eloquent model.

##### Basic Usage
``` use Illuminate\Foundation\Auth\User as Authenticatable;
use Jalameta\Support\Database\Eloquent\UuidAsPrimaryKey;

class User extends Authenticable
{
    use UuidAsPrimaryKey;
    
    protected $primaryKey = 'uuid';
}
```

J
