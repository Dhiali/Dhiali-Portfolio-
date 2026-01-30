#!/bin/bash

# Git Cleanup Script for Portfolio Repository
# This script helps clean up accidentally committed node_modules and dist files

set -e  # Exit on error

echo "=========================================="
echo "Git Repository Cleanup Script"
echo "=========================================="
echo ""

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo "ERROR: Not in a git repository!"
    exit 1
fi

echo "Current directory: $(pwd)"
echo "Current branch: $(git branch --show-current)"
echo ""

# Check if node_modules or dist are tracked
TRACKED_FILES=$(git ls-files | grep -E "^(node_modules|dist)/" || true)

if [ -z "$TRACKED_FILES" ]; then
    echo "✓ Good news! No node_modules or dist files are currently tracked by git."
    echo ""
else
    echo "⚠ WARNING: Found tracked files in node_modules or dist:"
    echo "$TRACKED_FILES" | head -10
    if [ $(echo "$TRACKED_FILES" | wc -l) -gt 10 ]; then
        echo "... and $(echo "$TRACKED_FILES" | wc -l) more files"
    fi
    echo ""
    
    read -p "Do you want to remove these files from git tracking? (y/N) " -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo "Removing node_modules/ and dist/ from git tracking..."
        git rm -r --cached node_modules/ 2>/dev/null || true
        git rm -r --cached dist/ 2>/dev/null || true
        echo "✓ Files removed from git tracking"
        echo ""
        echo "Next steps:"
        echo "  1. Review changes: git status"
        echo "  2. Commit changes: git commit -m 'Remove node_modules and dist from tracking'"
        echo "  3. Push changes: git push origin main"
        echo ""
    fi
fi

# Check .gitignore
echo "Checking .gitignore..."
if [ -f .gitignore ]; then
    if grep -q "node_modules/" .gitignore && grep -q "dist/" .gitignore; then
        echo "✓ .gitignore properly configured"
    else
        echo "⚠ .gitignore exists but may not properly ignore node_modules/ and dist/"
    fi
else
    echo "⚠ WARNING: .gitignore file not found!"
fi
echo ""

# Check .gitattributes
echo "Checking .gitattributes..."
if [ -f .gitattributes ]; then
    echo "✓ .gitattributes file exists (handles line endings)"
else
    echo "ℹ .gitattributes file not found. Consider adding it for consistent line endings."
fi
echo ""

# Check git config for line endings
echo "Checking git line ending configuration..."
AUTOCRLF=$(git config --get core.autocrlf || echo "not set")
echo "  core.autocrlf = $AUTOCRLF"
if [ "$AUTOCRLF" != "true" ] && [ "$AUTOCRLF" != "input" ]; then
    echo "  Recommendation: Set core.autocrlf to 'true' on Windows or 'input' on Linux/Mac"
    echo "  Command: git config --global core.autocrlf true"
fi
echo ""

# Check for uncommitted changes
if ! git diff-index --quiet HEAD -- 2>/dev/null; then
    echo "⚠ You have uncommitted changes:"
    git status --short
    echo ""
fi

# Check branch status
echo "Checking branch status..."
git fetch origin 2>/dev/null || true
CURRENT_BRANCH=$(git branch --show-current)
if git rev-parse origin/$CURRENT_BRANCH > /dev/null 2>&1; then
    LOCAL=$(git rev-parse HEAD)
    REMOTE=$(git rev-parse origin/$CURRENT_BRANCH)
    BASE=$(git merge-base HEAD origin/$CURRENT_BRANCH)
    
    if [ "$LOCAL" = "$REMOTE" ]; then
        echo "✓ Your branch is up to date with origin/$CURRENT_BRANCH"
    elif [ "$LOCAL" = "$BASE" ]; then
        echo "⚠ Your branch is behind origin/$CURRENT_BRANCH"
        echo "  Run: git pull origin $CURRENT_BRANCH"
    elif [ "$REMOTE" = "$BASE" ]; then
        echo "ℹ Your branch is ahead of origin/$CURRENT_BRANCH"
        echo "  Run: git push origin $CURRENT_BRANCH"
    else
        echo "⚠ Your branch has diverged from origin/$CURRENT_BRANCH"
        echo "  Run: git pull --rebase origin $CURRENT_BRANCH"
    fi
else
    echo "ℹ Remote branch origin/$CURRENT_BRANCH not found"
fi
echo ""

echo "=========================================="
echo "Cleanup script completed!"
echo "=========================================="
