# Layla: Migration zu Joomla 6

## Ziel

Das Joomla51-Template Layla läuft nativ unter Joomla 6, ohne dass das Plugin **Behaviour – Backward Compatibility 6** aktiviert sein muss. Funktionsumfang und Optik bleiben unverändert.

## Ausgangslage

Basis ist die bereits durchgeführte Joomla-6-Migration von Layla (Version 6.2.0-r5). Für Release 6.2.1 wurde dieser Stand gegen den Joomla-6.2.0-Quellcode geprüft und an den gemeinsamen Stand von Emani und Nina angeglichen.

Prüfgrundlage: PHP-Lint unter PHP 8.3, Laufzeittests unter Joomla 6.2.0 mit PHP 8.4 (Compat-Plugin deaktiviert), statische Suche nach Legacy-Code ohne Treffer, alle `Text::_()`-Schlüssel gegen die Joomla-6-Sprachdateien abgeglichen.

## Änderungen in 6.2.1

- **`$app->input` → `getInput()`** in `index.php`, `error.php`, `helper.php`, `elements/subgroup.php`. Joomla 6 protokolliert den Zugriff auf die `input`-Eigenschaft als veraltet.
- **Fehlerseite:** `jdoc:include` für metas, styles und scripts im `<head>` ergänzt. Vorher wurden die per WebAssetManager angeforderten Styles nie ausgegeben, und Plugins konnten keine Skripte einfügen (`onBeforeCompileHead`). Seitentitel unverändert. Sprachschlüssel `JERROR_LAYOUT_MIS_TYPED_ADDRESS` → `JERROR_LAYOUT_MISTYPED_ADDRESS` (in Joomla 6 umbenannt).
- **WebAssetManager:** 57 Aufrufe von `addStyleDeclaration`, `addScriptDeclaration`, `addStyleSheet`, `addScript` (Entfernung in Joomla 7) → `addInlineStyle()`, `addInlineScript()`, `registerAndUseStyle/Script()` – in `inc/`, Overrides, `BlockHelper`, Template-Optionen, Offline-Seite und den Custom-CSS-Quellen.
- **Frontend-Beitragsformular:** farbige Status-Auswahl war unsichtbar (weiße Schrift auf weißem `select-bg.svg`) → CSS-Regel in `css/base/template.css` und `media/css/base/template.css`.
- **Installer:** Bereinigung alter Override-Ordner früherer Versionen nur noch beim Update von Versionen vor 2.0.0 (Vorgängerversion aus `manifest_cache`). Vorher wurden bei jedem Update auch kundeneigene Overrides in diesen Ordnern gelöscht.
- `_JEXEC`-Guards ergänzt (`helper.php`, `elements/infobox.php`, `noticebox.php`, `titlebox.php`, `inc/layouts/base.php`), `composer.json` Plattform PHP 8.3.
- Version 6.2.1 (einheitlich mit Emani und Nina), Autor „Joomla51 / Alpenrand Digital“, Hinweis „Project by Alpenrand Digital“ auf dem Details-Reiter des Template-Stils.

## Laufzeittests (Joomla 6.2.0, PHP 8.4, Compat-Plugin aus)

Update-Installation, Startseite, Alternative- und Masonry-Blog, Beitragsansicht, Smart Search, 404-Seite, Frontend-Bearbeitung: keine PHP-Fehler, keine Konsolenfehler aus dem Template.
