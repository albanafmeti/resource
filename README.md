**Resource** is a package which is needed to build resources for JSON responses etc. I have tested it using Phalcon PHP framework.

## How to use

Suppose in our app (which can be any framework like Laravel, Phalcon etc.) we have some models like *User*, *Post*, *Comment*. These models have their relationships between each other. So a user can have many posts, a post can have many comments, a user can have many comments, a post belongs to a user, a comment belongs to a post, a comment belongs to one user.

Regardless of the framework we are using we can access these relations via models in this form:

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
            "userId"          => $this->userId,
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
            "postId"          => $this->postId,
            "userId"        => $this->userId,
            "content"        => $this->content,
            "createdAt"      => $this->createdAt
            
        ];
    }
}
```
