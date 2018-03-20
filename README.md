# electricity_bill_collection
Electricity Bill collection system with android apps and web app.

This is a project showing the implementation of a electricity bill collection. There are two android apps and web app in this project.

PHP is used for web app and backend API development for the android app.

The workflow goes as:
1. Collector collects the bill amount from the consumer and uses the "consumer" app to update the bill collection table.
2. There are single collector assigned to a specific area which will be decided by the web admin.
3. The collector has submit all his collection to the supervisor.
4. Again a single supervisor will be assigned to a group of collectors who will keep about all the accounts of collectors under him.
5. Finally each supervisor has to submit all the collected amount the admin.

To solve this problem there are two android apps:
1. Collector app: A collector can login with his username and password in the app. There he enters the unique consumer id of the consumer where he will get all the information about the consumer including his basic informationa and amount he has to pay. The collector update the amount collected from the consumer in the app. Now this update will be reported to the web admin and the supervisor alloted to the collector.
2. Supervisor app: Here supervisor can login in the app with his username and password. Now supervisor can see all logs about all the collecters assigned to him. Supervisor then collects money from collector and send money to admin and update the app with the reference number of transaction and a photo as proof.
3. Web Admin: Here the admin logs in the web and updates all the information, add new information and can see and control all transcation.


# Screen Shots

# Web admin

![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_0.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_1.PNG)
:-------------------------:|:-------------------------:
![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_2.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_3.PNG)
:-------------------------:|:-------------------------:
![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_4.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_5.PNG)
:-------------------------:|:-------------------------:
![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_6.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_7.PNG)
:-------------------------:|:-------------------------:
![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_8.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_9.PNG)
:-------------------------:|:-------------------------:
![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_10.PNG)  |  ![](https://github.com/aayushsinha44/electricity_bill_collection/blob/master/screenshot/web_app/screen_shot_9.PNG)
