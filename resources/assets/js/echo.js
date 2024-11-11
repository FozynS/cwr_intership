import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io; 
window.Echo = new Echo({
  broadcaster: 'socket.io',
  host: window.location.hostname + ':6001',
  authEndpoint: '/broadcasting/auth',
  auth: {
    headers: {
      'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content
    }
  },
});

export default window.Echo;