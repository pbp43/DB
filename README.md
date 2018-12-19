#Back End

❖	Database name cal.
     Tables in Cal:

1.	Table Users: It allows frontend to login with correct id and password.Also It allows frontend to create new user
2.	Calorietrack: This table provide frontend to track user’s total calories  as well as other nutrition info.
3.	Favfood: it allows frontend to save,view ,and delete favorite food of specific user.
4.	History: it keep track of html search requests and provide last 10 recent  search history. 
❖	 Database replication and automatic failover
➢	   Created serverA and serverB where we configure serverA as master and serverB as slave.
➢	 When both servers are running than serverA runs listener file and handles html requests. Also, performed live sync to its slave server.
For the automatic failover ,Slave server  runs a script that continually check  master server status.If master server get failed then Slave automatically launch its listener file and handles request from html.
