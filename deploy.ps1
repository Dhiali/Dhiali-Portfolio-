# PowerShell script to rebuild and deploy a Vite React project to GitHub Pages

# Step 1: Navigate to the project directory
cd "C:\Users\dhial\Desktop\Portfolio website\Dhiali-Portfolio-"

# Step 2: Install dependencies (if needed)
Write-Host "Installing dependencies..."
npm install

# Step 3: Build the project
Write-Host "Building the project..."
npm run build

# Step 4: Commit and push the dist folder to the gh-pages branch
Write-Host "Deploying to GitHub Pages..."
git add .
git commit -m "Deploy: Rebuild and deploy to GitHub Pages"
git push origin main

# Step 5: Deploy dist folder to gh-pages branch
git subtree push --prefix dist origin gh-pages

Write-Host "Deployment complete!"