**Resource** is a package which is needed to build resources for JSON responses etc. I have tested it using Phalcon PHP framework.

## How to use

Suppose in our app (which can be any framework like Laravel, Phalcon etc.) we have some models like *User*, *Post*, *Comment*. These models have their relationships between each other. So a user can have many posts, a post can have many comments, a user can have many comments, a post belongs to a user, a comment belongs to a post, a comment belongs to one user.

Regardless of used framework we can access these relations via models in this form:

 ```php
 <?php
 
 $user->posts;
 $post->user;
 
 $post->comments;
 $comment->post;
 
 $user->comments;
 $comment->user;
 ```
 
 Sometime, when we want to return JSON from our app routes (API endpoints) we don't want to return the whole model object with all its fields, or sometimes we want to return any relationship field togethere with the core fields of a model.
 In this case we need a package like **Resource** which is very helpful in such cases.

For our example we have to build 3 classes which will extend *\Noisim\Resource\Resource* class.

```php
<?php

class UserResource extends Resource {

    public function toArray() {
        return [

            "id"             => $this->id,
            "name"           => $this->name,
            "email"          => $this->email,
            "createdAt"      => $this->createdAt
            
        ];
    }
}


class PostResource extends Resource {

    public function toArray() {
        return [

            "id"             => $this->id,
            "userId"         => $this->userId,
            "title"          => $this->title,
            "content"        => $this->content,
            "createdAt"      => $this->createdAt
          
        ];
    }
}

class CommentResource extends Resource {

    public function toArray() {
        return [

            "id"             => $this->id,
            "postId"         => $this->postId,
            "userId"         => $this->userId,
            "content"        => $this->content,
            "createdAt"      => $this->createdAt
            
        ];
    }
}

```

### Simple example

In our controller we can return resources in this way:

```php
<?php

namespace App\Controllers;

class PostController extends Controller {

    public function getAll() {

        $posts = $this->funnelService->getAll();

        // We pass the list of posts as an argument to the PostResource 
        return new PostResource($posts);
    }
    
    public function getById($id) {

        $post = $this->funnelService->getById($id);
        
        // We pass the post as an argument to the PostResource 
        return new PostResource($post);
    }
```
Depending by the framework the conversion of such responses to JSON needs to be handled by the framework itself. *PostResource* is an instance that can be serialized to JSON easily since it implements JsonSerializable.

#### Returning extra relationship fields

```php
<?php

namespace App\Controllers;

class PostController extends Controller {

    public function getAll() {

        $posts = $this->funnelService->getAll();

        // We pass the list of posts as an argument to the PostResource 
        return (new PostResource($posts))->with(function($post) {
           return [
              "comments" => new CommentResource($post->comments)
           ];
        });
    }
```

The above example appends a *comments* field to the returned data for every post instance. *comments* contains the list of comments for the related post. In this case we use the `with` method which accepts a closure like an argument.

#### Filtering fields


```php
<?php

namespace App\Controllers;

class PostController extends Controller {

    public function getAll() {

        $posts = $this->funnelService->getAll();

        // We pass the list of posts as an argument to the PostResource 
        return (new PostResource($posts))->only(['id', 'title', 'content']);
    }
```
In this example we use `only` method which filters fields, and the returned data will contains instances with the specified fields only.
We can use the method `except` if we want to exclude some fields:

```php
<?php
return (new PostResource($posts))->exclude(['userId', 'createdAt']);

```
