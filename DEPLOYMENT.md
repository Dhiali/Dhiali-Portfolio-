# Deployment Instructions for GitHub Pages

This portfolio is deployed to GitHub Pages using GitHub Actions.

## Deployment Process

When you push to the `main` branch, the GitHub Actions workflow automatically:
1. Installs dependencies
2. Builds the React/Vite application
3. Deploys the built files to GitHub Pages

## Important: GitHub Pages Settings

**You MUST configure GitHub Pages settings correctly:**

1. Go to your repository on GitHub
2. Click **Settings** → **Pages** (in the left sidebar)
3. Under "Build and deployment":
   - **Source**: Select **GitHub Actions** (NOT "Deploy from a branch")
4. Save the settings

## Troubleshooting 404 Errors

If you're getting a 404 error:

### 1. Check GitHub Pages Source
Make sure GitHub Pages is set to use **GitHub Actions** as the source (not a branch like `gh-pages`).

### 2. Delete Old gh-pages Branch
If you have an old `gh-pages` branch, delete it:
```bash
git push origin --delete gh-pages
```

### 3. Check Workflow Runs
Go to the **Actions** tab in your repository to see if the deployment workflow ran successfully.

### 4. Verify Base Path
The `vite.config.js` file has `base: '/Dhiali-Portfolio-'` which must match your repository name exactly.

### 5. Wait for Deployment
After merging to main, wait 1-2 minutes for GitHub Actions to build and deploy.

## Manual Deployment (Not Recommended)

If you need to manually trigger a deployment:
1. Go to **Actions** tab
2. Click on "Deploy to GitHub Pages" workflow
3. Click **Run workflow** → **Run workflow**

## Site URL

After successful deployment, your site will be available at:
https://dhiali.github.io/Dhiali-Portfolio-/

## Files Involved

- `.github/workflows/deploy.yml` - GitHub Actions workflow
- `vite.config.js` - Vite configuration with correct base path
- `public/.nojekyll` - Prevents GitHub Pages from using Jekyll processing
