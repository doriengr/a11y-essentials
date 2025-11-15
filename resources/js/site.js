import Alpine from 'alpinejs';

import './stores/navigation';

import accordion from './components/accordion';
import dialog from './components/dialog';
import form from './components/form';
import mainNavigation from './components/mainNavigation';
import profileNavigation from './components/profileNavigation';
import sideNavigation from './components/sideNavigation';
import tablist from './components/tablist';

Alpine.data('accordion', accordion);
Alpine.data('dialog', dialog);
Alpine.data('form', form);
Alpine.data('mainNavigation', mainNavigation);
Alpine.data('profileNavigation', profileNavigation);
Alpine.data('sideNavigation', sideNavigation);
Alpine.data('tablist', tablist);

window.Alpine = Alpine;
