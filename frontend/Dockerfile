# Use the official Node.js image
FROM node:20


# Set the working directory
WORKDIR /app

# Copy the application files
COPY . .

# Install npm dependencies
RUN npm install --force

# Expose the port
EXPOSE 3000

# Start the Vite development server
CMD ["npm", "run", "dev"]
