<?php

return [

    'problem_labels' => [
        'critical' => 'Sehr kritische Probleme',
        'serious' => 'Schwerwiegende Probleme',
        'moderate' => 'Moderate Probleme',
        'minor' => 'Geringfügige Probleme',
        'wcag2a' => 'Probleme in Konformitätsstufe A',
        'wcag2aa' => 'Probleme in Konformitätsstufe AA',
        'wcag2aaa' => 'Probleme in Konformitätsstufe AAA',
        'passes' => 'Erfolgreich abgeschlossene Überprüfungen',
    ],

    'problem_descriptions' => [
        'critical' => 'Diese Fehler blockieren die wesentliche Nutzung für betroffene Personen.',
        'serious' => 'Entdeckte Probleme sollten überprüft und gelöst werden, da sie Verstöße gegen die WCAG darstellen.',
        'moderate' => 'Diese entdeckten Probleme können bestimmte Nutzergruppen beeinträchtigen, aber die Anwendung ist weiterhin nutzbar.',
        'minor' => 'Diese Probleme beeinträchtigen die Zugänglichkeit kaum, aber sollten für eine Barrierefreiheit verbessert werden.',
        'wcag2a' => 'Diese Konformitätsstufe bildet die Basis vieler Regeln bezüglich a11y. Die Probleme sollten auf jeden Fall verbessert werden.',
        'wcag2aa' => 'Wie die Konformitätsstufe A, ist auch dieses Level gesetzlich einzuhalten, sodass auch diese Probleme in Augenschein genommen werden sollten.',
        'wcag2aaa' => 'Diese Konformitätsstufe ist nicht gesetzlich vorgeschrieben, aber verbessert weiterhin die User Experience.',
        'passes' => 'Folgende Überprüfungen wurden bereits erfolgreich abgeschlossen und müssen nicht weiter angepasst werden.',
    ],
];
