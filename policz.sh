find . -name '*.php' | xargs wc -l > "policzone linie.txt"
find . -name '*.js' | xargs wc -l >> "policzone linie.txt"
find . -name '*.css' | xargs wc -l >> "policzone linie.txt"
find . -name '*.html.twig' | xargs wc -l >> "policzone linie.txt"
