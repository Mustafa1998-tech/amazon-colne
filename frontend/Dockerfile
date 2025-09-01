FROM node:16 as build

# Set working directory
WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy project files
COPY . .

# Since this is a static site, we'll use nginx to serve the files
FROM nginx:alpine
COPY --from=build /app /usr/share/nginx/html
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Start the application
CMD ["nginx", "-g", "daemon off;"]
