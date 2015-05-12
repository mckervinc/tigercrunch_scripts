# tigercrunch_scripts
Back End PHP scripts for TigerCrunch

These scripts live on the AWS Server. These access our MYSQL Database.

Instructions:

The file that is in this folder (the .pem), is important. Don’t lose it! Put it in some folder on your machine.
Open Terminal, go to the folder where you put the .pem file, and run this command (this only needs to be done once I believe):
chmod 400 ihungry_test1.pem
To SSH into it, run this command:
ssh -i ihungry_test1.pem ec2-user@ec2-54-191-17-139.us-west-2.compute.amazonaws.com
Type yes whenever any prompts come up. Then you should be inside of it!
On the server, you want to run this command to change into the directory where you can edit files:
cd /var/www/html/
If you run the command ls you will see a test file that I made in the tutorial (phpinfo.php). If you type http://ec2-54-191-17-139.us-west-2.compute.amazonaws.com/phpinfo.php into your browser, you will see a test page.
Just as an aside, if we mess something up on the server, we can always make a new one.

Working with MYSQL:
After you login, you should NOT HAVE TO START MYSQL. But if, for some reason, when you do step two, and you get an error, run this command:
sudo service mysqld start
Then you can type mysql -u root -p, and a mysql prompt should come up. The password is ‘hunger’
The database to use is hunger_1994ampz; the command to enter is:
use hunger_1994ampz;
To see all the items in the database, run this command:
select * from FreeFood;

Transfer Files from Your Computer to the server:
Run this command, while you are NOT logged in to the server:
scp -i ihungry_test1.pem test.txt ec2-user@ec2-54-191-17-139.us-west-2.compute.amazonaws.com:/var/www/html
You will find your file in the html folder

Transfer Files from the Server to your computer:
Run this command, while not logged in to the server:
scp -i ihungry_test1.pem ec2-user@ec2-54-191-17-139.us-west-2.compute.amazonaws.com:~/SampleFile.txt ~/SampleFile2.txt
