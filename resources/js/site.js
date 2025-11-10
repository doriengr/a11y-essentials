import Alpine from 'alpinejs';

import './stores/navigation';

import accordion from './components/accordion';
import axeChecker from './components/axeChecker';
import dialog from './components/dialog';
import form from './components/form';
import mainNavigation from './components/mainNavigation';
import profileNavigation from './components/profileNavigation';
import sideNavigation from './components/sideNavigation';

Alpine.data('accordion', accordion);
Alpine.data('axeChecker', axeChecker);
Alpine.data('dialog', dialog);
Alpine.data('form', form);
Alpine.data('mainNavigation', mainNavigation);
Alpine.data('profileNavigation', profileNavigation);
Alpine.data('sideNavigation', sideNavigation);

window.Alpine = Alpine;
