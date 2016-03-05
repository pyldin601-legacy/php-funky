# php-funky
Functional Library for PHP.

At this time it consist of two classes - Option and Lambda.

## Option
Option is object that represents encapsulation of an optional value. It can be used as the return value of functions which may or may not return a meaningful value when they are applied.
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

