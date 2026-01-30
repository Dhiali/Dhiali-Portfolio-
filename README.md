# Dhiali Chetty Portfolio

A responsive portfolio website built with React and Vite, deployed to GitHub Pages.

## 🚀 Live Site

**URL:** https://dhiali.github.io/Dhiali-Portfolio-/

## 📋 Deployment Setup

This site uses the **gh-pages branch deployment** method.

### Configure GitHub Pages Settings

1. Go to your repository on GitHub: https://github.com/Dhiali/Dhiali-Portfolio-
2. Click **Settings** → **Pages** (in the left sidebar)
3. Under "Build and deployment":
   - **Source**: Select **Deploy from a branch**
   - **Branch**: Select **gh-pages** and **/ (root)**
4. Click **Save**

### Deploy the Site

To deploy updates to your live site:

```bash
npm run deploy
```

This command will:
1. Build your site (`npm run build`)
2. Push the built files to the `gh-pages` branch
3. GitHub Pages will automatically serve the updated site

**Note:** The first deployment may take 1-2 minutes to appear. Subsequent updates are usually faster.

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

## 🔧 Tech Stack

- React 18
- Vite 6
- Tailwind CSS
- Radix UI Components
- GitHub Pages (via gh-pages branch)
