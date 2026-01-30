# Dhiali Chetty Portfolio

A responsive portfolio website built with React and Vite, deployed to GitHub Pages.

## 🚀 Live Site

**URL:** https://dhiali.github.io/Dhiali-Portfolio-/

## 📋 Deployment Setup

This site uses **GitHub Actions** for automated deployment to GitHub Pages.

### Configure GitHub Pages Settings

1. Go to your repository on GitHub: https://github.com/Dhiali/Dhiali-Portfolio-
2. Click **Settings** → **Pages** (in the left sidebar)
3. Under "Build and deployment":
   - **Source**: Select **GitHub Actions**
4. Save the settings

### Deploy the Site

**Automatic Deployment (Recommended):**

Simply push your changes to the `main` branch:

```bash
git add .
git commit -m "Your changes"
git push origin main
```

GitHub Actions will automatically build and deploy your site. Check the Actions tab for deployment status.

**Local Deployment (Alternative):**

```bash
npm run deploy
```

**Note:** Local deployment requires network access to GitHub. If you encounter "Could not resolve host: github.com" errors, use the automatic deployment method instead.

## 🛠️ Development

### Install Dependencies
```bash
npm install
```

### Run Development Server
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

The built files will be in the `dist/` folder.

## 🔍 Troubleshooting

### Git Issues

If you encounter git-related issues (accidentally committing `node_modules/` or `dist/`, line ending warnings, or push failures), see:

- **[GIT_ISSUES_FIX.md](./GIT_ISSUES_FIX.md)** - Comprehensive guide for fixing common git problems
- **cleanup-git.sh** - Helper script to check and fix repository issues

Run the cleanup script:
```bash
bash cleanup-git.sh
```

### Common Issues

- **Push rejected (non-fast-forward)**: Your local branch is behind remote. Run `git pull origin main` before pushing.
- **LF/CRLF warnings**: The `.gitattributes` file handles this automatically. Configure git with `git config --global core.autocrlf true` on Windows.
- **Accidentally committed node_modules**: See GIT_ISSUES_FIX.md for removal instructions.

## 🔧 Tech Stack

- React 18
- Vite 6
- Tailwind CSS
- Radix UI Components
- GitHub Pages (via GitHub Actions)
