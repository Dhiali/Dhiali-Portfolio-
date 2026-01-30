# MIME Type Error Analysis

## Error Message
```
Failed to load module script: Expected a JavaScript-or-Wasm module script but the server responded with a MIME type of "text/jsx". Strict MIME type checking is enforced for module scripts per HTML spec.
```

## Root Cause

The MIME type error occurs because **GitHub Pages is serving the source `index.html` file instead of the built version from the `dist` folder**.

### What's Happening

**Source index.html (Line 14):**
```html
<script type="module" src="/src/main.jsx"></script>
```

This references a JSX file directly, which:
- ✅ Works in development (Vite dev server transforms JSX on-the-fly)
- ❌ Fails in production (browsers can't execute JSX directly)

**Built dist/index.html (Line 10):**
```html
<script type="module" crossorigin src="/Dhiali-Portfolio-/assets/index-gb0VJw5u.js"></script>
```

This references compiled JavaScript bundles, which:
- ✅ Works in production (proper MIME type: application/javascript)
- ✅ Has all JSX compiled to regular JavaScript

## Why This Is Happening

The deployment workflow in `.github/workflows/deploy.yml` is correctly configured to:
1. Build the application: `npm run build`
2. Upload the `dist` folder: `path: './dist'`
3. Deploy to GitHub Pages

**However, this PR hasn't been merged to `main` yet**, so:
- The workflow has not run
- GitHub Pages is likely still serving from the old `gh-pages` branch
- The `gh-pages` branch is empty or has outdated content

## Verification

### Build Process Works Correctly ✅

```bash
$ npm run build
vite v6.3.5 building for production...
✓ 2017 modules transformed.
✓ built in 3.51s
```

**Built dist folder contains:**
- ✅ `index.html` with proper script tags
- ✅ `assets/index-*.js` (compiled JavaScript)
- ✅ `assets/index-*.css` (compiled styles)
- ✅ `.nojekyll` file (prevents Jekyll processing)

**Built index.html correctly references:**
```html
<script type="module" crossorigin src="/Dhiali-Portfolio-/assets/index-gb0VJw5u.js"></script>
<link rel="stylesheet" crossorigin href="/Dhiali-Portfolio-/assets/index-BguHyMp2.css">
```

### Workflow Configuration Is Correct ✅

The deployment workflow:
1. Checks out code
2. Installs Node.js 18
3. Runs `npm ci`
4. Runs `npm run build` (creates dist folder)
5. Uploads `./dist` as Pages artifact
6. Deploys to GitHub Pages

## Solution

To fix the MIME type error, the user must:

### 1. Merge This PR to Main

The PR must be merged to the `main` branch so the deployment workflow can run.

### 2. Configure GitHub Pages Settings

**Critical:** GitHub Pages must be set to use **GitHub Actions** as the source:

1. Go to: `Settings → Pages`
2. Under "Build and deployment":
   - **Source:** Select "GitHub Actions" (NOT "Deploy from a branch")
3. Click Save

### 3. Delete the Old gh-pages Branch

The old `gh-pages` branch should be deleted to avoid conflicts:

```bash
git push origin --delete gh-pages
```

### 4. Wait for Deployment

After merging to `main`:
- The workflow will automatically trigger
- The build will complete in ~2-3 minutes
- The site will be deployed with the correct built files

## Timeline

1. **Before Merge:** MIME type error (serving source files)
2. **After Merge:** Workflow runs and deploys dist folder
3. **After Settings Change:** GitHub Pages serves from GitHub Actions
4. **Result:** Site loads correctly with compiled JavaScript

## Technical Details

### Why JSX Can't Be Served Directly

Browsers cannot execute JSX syntax directly:
- JSX: `<div>Hello</div>` (not valid JavaScript)
- Compiled: `React.createElement("div", null, "Hello")` (valid JavaScript)

Vite's build process:
1. Transforms JSX → JavaScript
2. Bundles all modules
3. Minifies code
4. Generates proper HTML with correct script tags

### MIME Type Requirements

According to the HTML spec, `<script type="module">` requires:
- Valid MIME type: `application/javascript` or `text/javascript`
- Invalid: `text/jsx`, `text/plain`, etc.

GitHub Pages serves `.js` files with correct MIME type, but `.jsx` files with incorrect MIME type.

## Conclusion

The MIME type error is not a bug in the code or build process. It's a configuration issue where GitHub Pages is serving the wrong directory. Once the PR is merged and GitHub Pages settings are updated, the error will be resolved.
