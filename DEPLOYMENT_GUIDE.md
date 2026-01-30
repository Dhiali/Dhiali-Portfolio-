# Deployment Guide - gh-pages Branch Method

This portfolio uses the **gh-pages branch deployment** method for GitHub Pages.

## How It Works

1. You run `npm run deploy` locally
2. The script builds your site and pushes the built files to the `gh-pages` branch
3. GitHub Pages automatically serves your site from the `gh-pages` branch

## Initial Setup

### Configure GitHub Pages Settings

**This is a one-time setup:**

1. Go to your repository: https://github.com/Dhiali/Dhiali-Portfolio-
2. Click **Settings** → **Pages**
3. Under "Build and deployment":
   - **Source**: Select **Deploy from a branch**
   - **Branch**: Select **gh-pages** / **/ (root)**
4. Click **Save**

## Deploying Updates

Whenever you want to deploy changes to your live site:

```bash
# 1. Make sure all your changes are committed
git add .
git commit -m "Your commit message"
git push origin main

# 2. Deploy to GitHub Pages
npm run deploy
```

The `npm run deploy` command will:
- Run `npm run build` to create optimized production files
- Push the contents of the `dist` folder to the `gh-pages` branch
- GitHub Pages will automatically update your live site (takes 1-2 minutes)

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

1. Check that GitHub Pages is configured correctly (Settings → Pages)
2. Verify the `gh-pages` branch exists in your repository
3. Wait 1-2 minutes for GitHub Pages to rebuild
4. Clear your browser cache (Ctrl+Shift+R or Cmd+Shift+R)

### 404 errors on the deployed site

1. Verify `vite.config.js` has `base: '/Dhiali-Portfolio-'`
2. Make sure the repository name matches exactly
3. Ensure `.nojekyll` file is present in the `dist` folder

### Build errors

```bash
# Clean install
rm -rf node_modules package-lock.json
npm install

# Try building again
npm run build
```

## Notes

- The `gh-pages` branch is automatically managed by the `gh-pages` npm package
- Never manually edit the `gh-pages` branch
- The source code stays on `main` branch
- The built site is on `gh-pages` branch
