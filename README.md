**Resource** is a package which is needed to build resources for JSON responses etc. I have tested it using Phalcon PHP framework.

## How to use

Suppose in our app (which can be any framework like Laravel, Phalcon etc.) we have some models like *User*, *Post*, *Comment*. These models have their relationships between each other. So a user can have many posts, a post can have many comments, a user can have many comments, a post belongs to a user, a comment belongs to a post, a comment belongs to one user.

Regardless of the framework we are using we can access these relations via models in this form:

 ```php
 <?php
 
 $user->posts;
 $post->user;
 
 $post->comments;
 $comment->post
 
 $user->comments;
 $comment->user;
 ```
