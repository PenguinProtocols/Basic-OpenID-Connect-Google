# Basic OpenID Connect: Google

Basic OpenID Connect boilerplate using Google to authenticate users. Uses Google's OAuth 2.0 APIs: https://developers.google.com/identity/protocols/OpenIDConnect

# This project aims to:

 - Provide very *simple and lightweight* PHP code.
 - Do one thing and one thing only: *user authentication*, which allows users to log in through Google without additional complexities. 
 - Facilitate *clear and step-by-step documentation* to get you up and running in no time!

# How to use

This repository is made so that you don't have to implement your own user and password authentication system. Rather, you can have users log in with their existing Google account, and the only thing you have to do is to connect with Google to validate the user information, and then save user information in a local database. Saving of user information in a database is left up to you. This repository is only concerned with authenticating users. 

# Getting started

## Activating your Google API keys

In order to be able to authenticate users through Google, you need to have an active API key from Google. For this, go to the Google Developers Console (https://console.developers.google.com) and under *APIs & auth* go to *Credentials*. Here, create a new Client ID. You will need to make sure your *Redirect URIs* in the Google Developer Console include the URL you will be using. Example: say your login page is located at http://example.com/login.php, then you need to add *http://example.com/login.php* to this list. Google will not accept http://localhost as a valid domain so for testing purposes you will need to try this on a fully qualified domain name. 
 
## Configure your login script

Copy your Client ID and the Client Secret from the Google Developer Console to the PHP login script. That is all to get started! Open the login.php page in your browser and try logging in. You should see a Google login prompt (if you're not logged in), and a consent form from Google. Once confirmed, you are sent back to login.php where your user details are being shown. You can then add your own functionalities (user validation, saving users in database, etc.). 

# Errors

See below for common Google OAuth 2.0 error codes

## 401: Invalid_client

The client_id you provided is not valid. Make sure you copy the whole Client ID from the Developers Console (it should end in *apps.googleusercontent.com*).

## 400: Redirect_uri_mismatch

The redirect URI is not whitelisted in the Developers Console. Either you forgot to add it there (include the full URL including the pagename, e.g. http://example.com/login.php), or you need to wait a few minutes until it is activated.

# To Do

 - Consider excluding JWT script and implement straight decoding, given that data from Google is to be trusted (if received directly like this). 
 - Capture specific error from Google and show this instead of 'expired token'.
 - Figure out which PHP version is required and add list of prerequisites (PHP, Curl...?)
 - Better explain openid.realm usage (and make clear it's optional)
