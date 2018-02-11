General objective:
Create a fully functioning messaging board

Tools:
PHP, OOP, MySQL, jQuery, AJAX, XML / JSON

Requirements
PHP7 code must be object oriented. Should not use any PHP framework if possible.
PHP7 with MySQL the database will also have to communicate through a designated object.
System should fully support JavaScript switched on and off. (If JavaScript is disabled, the system should still be useable.)

AJAX query results must be sent in XML or JSON format.
All form fields must be validated, even if the field is optional, but contains some input from user.
In case of an error, fields must be marked with incorrect information.
The name must consist of two inputs - Name and Last name. Only alphabetical symbols should be allowed in these fields. No numbers. 
Date of birth must be correctly formatted, the existing date (can not be February 30th.) And can not be a future date.
E-mail address is optional, but if entered, must be validated.
Message field is mandatory.
All form fields should be protected against XSS, SQL / JavaScript intections. HTML input should not be allowed. Inputs containing HTML text should be cleaned from any HTML code before they are saved in database.

If JavaScript is enabled:
After pressing the submit button, all fields have become inactive (not editable), and instead loader diagram should appear.
In case of success, the most recent message should be placed on top using JavaScript The oldest message should be removed from the screen. All form fields should be activated again.
In case any errors are detected, fields must be marked and fields should be activated so that user can edit their input.
In both cases, the loader should disappear and the button should appiear instead.

If JavaScript is turned off:
Pressing the button should reload the page. 

All messages should be paginated. Number of posts per page should be defined in constants.

If an e-mail was provided, the name and last name should become a link. (By clicking on the full name, an e-mail is triggered.)

Year must be calculated from the current date and the date of birth entered.

Database structures should be created by you.

Bonus points:
Design Patterns are employed (Singleton, Factory, etc.)
The code should be clean, neat, with comments in English.

result:
Full source code and the database structure should be sent back to us as a proof of work done. 