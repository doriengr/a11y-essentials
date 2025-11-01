import Alpine from 'alpinejs';
import accordion from './components/accordion';
import form from './components/form';
import sideNavigation from './components/sideNavigation';

Alpine.data('accordion', accordion);
Alpine.data('form', form);
Alpine.data('sideNavigation', sideNavigation);

window.Alpine = Alpine;
