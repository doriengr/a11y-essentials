import Alpine from 'alpinejs';
import accordion from './components/accordion';
import sideNavigation from './components/sideNavigation';

Alpine.data('accordion', accordion);
Alpine.data('sideNavigation', sideNavigation);

window.Alpine = Alpine;
