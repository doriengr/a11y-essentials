<?php

return [

    'problem_labels' => [
        'critical' => 'Sehr kritische Probleme',
        'serious' => 'Schwerwiegende Probleme',
        'moderate' => 'Moderate Probleme',
        'minor' => 'Geringfügige Probleme',
        'A' => 'Probleme in Konformitätsstufe A',
        'AA' => 'Probleme in Konformitätsstufe AA',
        'AAA' => 'Probleme in Konformitätsstufe AAA',
        'none' => 'Probleme ohne Konformitätsstufe',
        'passes' => 'Erfolgreich abgeschlossene Überprüfungen',
    ],

    'problem_descriptions' => [
        'critical' => 'Diese Fehler blockieren die wesentliche Nutzung für betroffene Personen.',
        'serious' => 'Entdeckte Probleme sollten überprüft und gelöst werden, da sie Verstöße gegen die WCAG darstellen.',
        'moderate' => 'Diese entdeckten Probleme können bestimmte Nutzergruppen beeinträchtigen, aber die Anwendung ist weiterhin nutzbar.',
        'minor' => 'Diese Probleme beeinträchtigen die Zugänglichkeit kaum, aber sollten für eine Barrierefreiheit verbessert werden.',
        'A' => 'Diese Konformitätsstufe bildet die Basis vieler Regeln bezüglich a11y. Die Probleme sollten auf jeden Fall verbessert werden.',
        'AA' => 'Wie die Konformitätsstufe A, ist auch dieses Level gesetzlich einzuhalten, sodass auch diese Probleme in Augenschein genommen werden sollten.',
        'AAA' => 'Diese Konformitätsstufe ist nicht gesetzlich vorgeschrieben, aber verbessert weiterhin die User Experience.',
        'none' => 'Bei diesen entdeckten Probleme handelt es sich um »Best Practices«, die eingehalten werden sollten',
        'passes' => 'Folgende Überprüfungen wurden bereits erfolgreich abgeschlossen und müssen nicht weiter angepasst werden.',
    ],
];
