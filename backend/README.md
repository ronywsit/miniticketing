# Mini Ticketing

## Prerequisites
- Ensure that Docker is installed on your machine.

## Setup Instructions
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```
2. Start Docker Desktop and ensure it is running.
3. Navigate to the project directory:
   ```bash
   cd <repository-directory>
   ```
4. Run the following command to start the services:
   ```bash
   docker-compose build
   docker-compose up -d
   ```

## Accessing the Application
- Frontend: [http://localhost:3000](http://localhost:3000)
- Backend API: [http://localhost:8000](http://localhost:8000)

## Troubleshooting
- If you encounter issues with MongoDB not starting, ensure that the Docker volume for MongoDB has been removed and that the correct MongoDB version is specified in the `docker-compose.yml` file.
- Check the logs for any specific container using:
   ```bash
   docker-compose logs <service-name>
