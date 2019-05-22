# **User register endpoint**

### What is this do?
Simply register new user, By saving the User email and password to the database.
Also give the ability to login and log out.

### What steps to do?
Step 1: Setting up environment/Installing Symfony.

Step 2: Creating the User Entity.

Step 3: Creating the User table with migrations.

Step 4: Creating a way for a User to register.

Step 5: Creating the /login route.

Step 6: Creating an Authenticator.

Step 7: Creating an authenticated route.

Step 8: Logging Out.

### How to use?
Open Postman type your app’s url and:

The /register in the body section give it the keys {email, password, password_confirmation} with the values.
For new user.

The /register with keys {email, password}. For login.

The /profile is endpoint that requires a user to be logged in to.

The /logout is to give a user the ability to logout.


### Up coming:
Email validation

Caching

Docker

