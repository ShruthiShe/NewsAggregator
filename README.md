## News Aggregator

## Setup Instructions 

##git
git init
git add README.md
git commit -m "first commit"
git branch -M new_branch
git remote add origin https://github.com/ShruthiShe/News_Aggregator.git
git push -u origin new_branch


### Prerequisites:
- I am unable to work with Docker. I am getting an error, and even though I tried to solve it, I couldn't find a solution
- I haven’t worked on Docker yet
error msg as : An unexpected error occurred while executing a WSL command.

Either shut down WSL down with wsl --shutdown, and/or reboot your machine. You can also try reinstalling WSL and/or Docker Desktop. If the issue persists,



##Swagger/OpenAPI 
127.0.0.1:8000/api/documentation


Available Endpoints:
POST /api/auth/register - Register a new user
POST /api/auth/login - User login

GET /api/articles - Get all articles
GET /api/articles/{id} - Get a specific article
GET /api/articles/search - search

POST /api/preferences
GET /api/preferences


GET /api/personalized-feed

GET /api/fetch-newsapi
GET /api/fetch-nytimes
GET /api/fetch-guardian


I created a Mailtrap account for the password reset. I will receive the reset password message, then copy the link and paste it into Postman to hit the API and change the password.
