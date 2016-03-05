# php-funky
Functional Library for PHP.

At this time it consist of two classes - Option and Lambda.

## Option
Option is an object that represents encapsulation of an optional value. It can be used as the return value of functions which may or may not return a meaningful value when they are applied.
It has two subclasses - Some and None. First encapsulates the original data and the second if there is no data to encapsulate. 

Option has following methods:

`Option::get() - extracts encapsulated data from Some or throws OptionException if called on None.`
`Option::isEmpty() - returns FALSE on Some and TRUE on None.`
`Option::nonEmpty() - returns TRUE on Some and FALSE on None.`
`Option::getOrElse($else) - returns encapsulated data if called on Some or returns $else in case of None.`
`Option::getOrThrow($exceptionClass, ...$arguments) - returns encapsulated data if called on Some or throws exception if called on None.`
`Option::orElse(Option $other) - if called on Some - returns self or $other if called on None.`
`Option::map($callable) - applies $callable to the encapsulated data and returns result encapsulated by new Some object or returns itself if called on None.`
`Option::filter($callable) - tests encapsulated data using $callable predicate. Returns itself if predicate returns TRUE or if called on None and returns None of predicate returns FALSE.`
`Option::wrap($value, $reject = null) - static method that encapsulates $value into Some if that value isn't equals to $reject. In this case it will return None.`

and few helper functions:

`some($value) - encapsulates $value into Some object`
`none() - returns singleton instance of None object`
`wrap($value, $reject = null) - is synonym of Option::wrap static method`

### Example 1

We have following code:

```php
class UserService 
{
    ...
    
    public function find($id)
    {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $st->execute([$id]);
        
        return $st->fetch();
    }
    
    ...
}
```

Depending on the `PDO` documentation, method `fetch` in our case fetches the row from a result set or returns `FALSE` on empty result set. 
That means that we must check result of `find` method and keep in mind that result `FALSE` means that user with the specified `$id` not found:

```php
$user = $userService->find(10);

if ($user === FALSE) {
    throw new UserNotFoundException;
}

return view('userProfile.tmpl', compact('user'));
```

We could use `Option` in our `UserService` class:

```php
class UserService 
{
    ...
    
    public function find($id)
    {
        $st = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $st->execute([$id]);
        
        return wrap($st->fetch(), FALSE);
    }
    
    ...
}
```

In this case result of `find` method encapsulating into `Option` even if user not found. But it will be subclass `Some` if user fetched from database or subclass `None` if not. Second argument in helper function `wrap` tells what result means that no result. Default value is `null`.

And then our code will be like:

```php
$user = $userService->find(10)->getOrThrow(UserNotFoundException::class);

return view('userProfile.tmpl', compact('user'));
```

We do not need to interpret result of `find` method. We know that it always return `Some` if data present or `None` if not.
