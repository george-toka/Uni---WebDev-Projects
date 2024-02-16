# WebDev-Projects
<h3> Online Voting System + back-end/database manipulation </h3>

---REFER TO SHOWSCASE FOLDER TO VIEW MINI CLIPS OF THE FINAL RESULT--- <br>
<br>
In the context of my university course "Databases" each team was expected to pick a topic, (or come up with their own) of which
they would create an SQL Database. The flow of the individual tasks was to first create an entity-relationship diagram & its referential integrity diagram. 
Then using PuTTY, implement the Database based on the final structure and familiarise ourselves with the SQL syntax and capabilities.  
Ultimately, we had to implement a basic web-app for our topic of choice where we would showcase the functionality of our database by accessing
and editing various attributes. Along with the web-app we also had to create views, stored procedures, triggers and transactions. <br>
<br>
Our topic of choice was an Online Voting System for Greece. For the implementation of the web-app we used HTML,CSS and internal/external frameworks
like Grid,Flexbox & Bootstrap. For back-end manipulation we were asked to use PHP. The back-end scripts were basically already given as templates to create
and structure the web-app around them, but due to our differentiation of our topic from the suggested ones we went on to create our own. <br>
<br>
All of the php scripts are html with internal php except for the results.php which is supposed to run after the end of the elections to calculate
all needed results<br>
<br>
**Important note: Because the creation of the web-app and the various functionalities of our database was done at the same time, <br>
these functionalities couldn't be utilised due to lack of time. Nevertheless they are existent and fully functional in the database itself. <br>
<br>
In short, we have: 
* Welcome Page that takes you to login page, or results page (the middle button is unusable)
* Login page where we authenticate the user and check if he has already voted. We utilise protection from SQL injection
* Voting Page where each voter votes based on his province.That means he can vote certain candidates and can <br>
only put a maximum number of checks on his vote
* Thanks for Voting Page. We prevent from voting page callback so that nobody can vote multiple times.
* Results Page


