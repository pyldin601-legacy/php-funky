# php-funky
Functional Library for PHP.

At this time it consist of two classes - Option and Lambda.

## Option
Option is an object that represents encapsulation of an optional value. It can be used as the return value of functions which may or may not return a meaningful value when they are applied.
It has two subclasses - Some and None. First encapsulates the original data and the second if there is no data to encapsulate. 

For example, we have following code:

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
It means that we must check result of `find` method and keep in mind that result `FALSE` means that user with the specified `$id` not found:

```php
$user = $userService->find(10);

if ($user === FALSE) {
    throw new UserNotFoundException;
}

return view('userProfile.tmpl', compact('user'));
```

We could use Option in our `UserService` class:

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

In this case result of `find` method encapsulated into `Option` object even if user not found.
But it will be `Some` if user fetched from database or `None` if not. Second argument in helper function `wrap` tells what result means that no result. Default value is `null`.

And then our code will be like:

```php
$user = $userService->find(10)->getOrThrow(UserNotFoundException::class);

return view('userProfile.tmpl', compact('user'));
```

We do not need to interpret result of `find` method. We know that it always will be Option value: `Some` if data present or `None` if not.