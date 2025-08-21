## Réorganisation du code responsive

### Fichiers modifiés :

1. **assets/styles/base/_responsive.scss**
   - Regroupement de toutes les media queries dans un fichier centralisé
   - Organisation par breakpoints : 768px, 769px+, 480px
   - Sections : Hero Banner, Navbar, Footer, Forms

2. **assets/styles/components/_navbar.scss**
   - Suppression des media queries (déplacées vers _responsive.scss)
   - Commentaires ajoutés pour indiquer la nouvelle localisation

3. **assets/styles/layouts/_footer.scss**
   - Suppression des media queries footer (déplacées vers _responsive.scss)

4. **assets/styles/components/_form.scss**
   - Suppression des media queries form (déplacées vers _responsive.scss)

### Avantages de cette réorganisation :

- **Maintenance facilitée** : toutes les règles responsive sont dans un seul fichier
- **Cohérence** : même breakpoints utilisés partout
- **Performance** : réduction de la duplication de code
- **Lisibilité** : code plus organisé et plus facile à debugger

### Structure du fichier _responsive.scss :

```scss
// TABLET & MOBILE (max-width: 768px)
- Hero Banner responsive
- Navbar responsive (générale et hero)
- Footer responsive 
- Forms responsive

// DESKTOP (min-width: 769px)
- Masquage des burger menus

// MOBILE ONLY (max-width: 480px)
- Ajustements pour très petits écrans
```

La compilation a été testée et fonctionne correctement ✅
