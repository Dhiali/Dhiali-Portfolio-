# Dhiali Chetty Portfolio

A responsive portfolio website built with React and Vite, deployed to GitHub Pages.

## 🚀 Live Site

**URL:** https://dhiali.github.io/Dhiali-Portfolio-/

## 📋 Important: After Merging This PR

To fix the 404 error, you **MUST** complete these steps after merging:

### 1. Configure GitHub Pages Settings

1. Go to your repository on GitHub: https://github.com/Dhiali/Dhiali-Portfolio-
2. Click **Settings** → **Pages** (in the left sidebar)
3. Under "Build and deployment":
   - **Source**: Select **GitHub Actions** ⚠️ NOT "Deploy from a branch"
4. Click **Save**

### 2. Delete the Old gh-pages Branch

The old `gh-pages` branch is empty and conflicts with the new deployment method:

```bash
git push origin --delete gh-pages
```

### 3. Trigger Initial Deployment

After merging to `main`, the workflow will automatically run. You can also manually trigger it:
1. Go to the **Actions** tab
2. Click "Deploy to GitHub Pages"
3. Click **Run workflow** → **Run workflow**

### 4. Wait for Deployment

The deployment takes 1-2 minutes. Check the Actions tab to monitor progress.

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

## 📚 More Information

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed deployment instructions and troubleshooting.

## 🔧 Tech Stack

- React 18
- Vite 6
- Tailwind CSS
- Radix UI Components
- GitHub Pages (via GitHub Actions)
