# apiFilm User guide

## Login
**Login with Facebook** 

> permissions : *All*

> After your login, you receive a token, put this ?token_access=TOKEN after url
```
GET /v1/login
```

## Users
**List All Users**

> permissions : **Admin**

> Add parameters ?search=WORD on url for search on list of all User
```
GET /v1/users
```

**List User by ID**

> permissions : *Admin for all, UserLoged for own*

> Add parameters ?sort=FIELD on url for sort field of User, separate by comma ',' if you want have more than one parameters
```
GET /v1/users/@id
```

**Film Liked by User**

> permissions : *Admin and UserLoged*
```
GET /v1/users/@id/liked
```

**Film Watched by User**

> permissions : *Admin and UserLoged*
```
GET /v1/users/@id/watched
```

**Film Wanted by User**

> permissions : *Admin and UserLoged*
```
GET /v1/users/@id/wanted
```

**Create User**

> permissions : *Admin*
```
POST /v1/users
```

**Update User**

> permissions : *Admin for all, UserLoged for own*
```
PUT /v1/users/@id
```

**Delete All Users**

> permissions : *Admin*
```
DELETE /v1/users
```

**Delete User by ID**

> permissions : *Admin for all, UserLoged for own*
```
DELETE /v1/users/@id
```

## Films