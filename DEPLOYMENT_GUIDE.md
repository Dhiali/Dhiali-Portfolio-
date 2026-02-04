# Deployment Guide

> **Note:** If you want to deploy this project for your own use (fork/clone for your portfolio), see **[SELF_DEPLOYMENT_GUIDE.md](./SELF_DEPLOYMENT_GUIDE.md)** for a comprehensive step-by-step guide.

This guide is for deploying updates to the original Dhiali portfolio repository.

This portfolio uses **GitHub Actions** for automated deployment to GitHub Pages.

## How It Works

1. Push your changes to the `main` branch
2. GitHub Actions automatically builds and deploys your site
3. GitHub Pages serves your site at https://dhiali.github.io/Dhiali-Portfolio-/

**Note:** The `npm run deploy` command is also available for local deployments but requires network access to GitHub. The GitHub Actions workflow is the recommended deployment method.

## Initial Setup

### Configure GitHub Pages Settings

**This is a one-time setup:**

1. Go to your repository: https://github.com/Dhiali/Dhiali-Portfolio-
2. Click **Settings** → **Pages**
3. Under "Build and deployment":
   - **Source**: Select **GitHub Actions**
4. The workflow will automatically deploy on the next push to `main`

## Deploying Updates

### Method 1: Automatic Deployment (Recommended)

Whenever you want to deploy changes to your live site:

```bash
# Make sure all your changes are committed
git add .
git commit -m "Your commit message"
git push origin main
```

GitHub Actions will automatically:
- Build your site
- Deploy to GitHub Pages
- Your live site will update in 1-2 minutes

### Method 2: Local Deployment (Alternative)

If you need to deploy locally:

```bash
# Deploy to GitHub Pages using gh-pages package
npm run deploy
```

**Note:** This method requires network access to GitHub and may fail with "Could not resolve host: github.com" errors in restricted network environments. Use the automatic deployment method instead.

## Development Workflow

```bash
# Install dependencies (first time only)
npm install

# Start development server
npm run dev

# Build for production (to test locally)
npm run build

# Deploy to GitHub Pages
npm run deploy
```

## Troubleshooting

### Site not updating after deployment

1. Check that GitHub Pages is configured correctly (Settings → Pages → Source: GitHub Actions)
2. Check the Actions tab for deployment status: https://github.com/Dhiali/Dhiali-Portfolio-/actions
3. Wait 1-2 minutes for GitHub Pages to rebuild
4. Clear your browser cache (Ctrl+Shift+R or Cmd+Shift+R)

### GitHub Actions workflow fails

1. Check the Actions tab for error details
2. Verify `package-lock.json` is committed
3. Ensure all dependencies are correctly listed in `package.json`

### 404 errors on the deployed site

1. Verify `vite.config.js` has `base: '/Dhiali-Portfolio-'`
2. Make sure the repository name matches exactly
3. Check the build output in the Actions logs

### Build errors locally

```bash
# Clean install
rm -rf node_modules package-lock.json
npm install

# Try building again
npm run build
```

### "Could not resolve host: github.com" error

This error occurs when using `npm run deploy` in an environment without network access to GitHub. Use the GitHub Actions workflow instead by pushing to the `main` branch.

## Notes

- GitHub Actions automatically builds and deploys on every push to `main`
- The source code stays on `main` branch
- Built files are deployed directly to GitHub Pages
- You can manually trigger deployment from the Actions tab using "workflow_dispatch"
