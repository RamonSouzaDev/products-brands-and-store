#!/bin/bash

# Laravel Product Search - WSL Setup Script
# Author: Ramon Mendes (dwmom@hotmail.com)
# For WSL users who can't access Windows files directly

echo "WSL Setup Helper for Laravel Product Search"
echo "=========================================="
echo ""
echo "If you're having trouble accessing files from WSL, try one of these solutions:"
echo ""
echo "1. Copy the project to your WSL filesystem:"
echo "   mkdir -p ~/projects"
echo "   cp -r /mnt/c/Users/YOUR_USERNAME/OneDrive/Documentos/products-brands-and-store ~/projects/"
echo "   cd ~/projects/products-brands-and-store"
echo ""
echo "2. Or clone the repository directly in WSL:"
echo "   cd ~"
echo "   git clone https://github.com/RamonSouzaDev/products-brands-and-store.git"
echo "   cd products-brands-and-store"
echo ""
echo "3. Then run the setup:"
echo "   ./setup-simple.sh"
echo ""
echo "4. Alternative: Use the Git Bash script from Windows:"
echo "   # From Windows Git Bash or PowerShell:"
echo "   ./setup-gitbash.bat"
echo ""
echo "For more information, visit:"
echo "https://github.com/RamonSouzaDev/products-brands-and-store"