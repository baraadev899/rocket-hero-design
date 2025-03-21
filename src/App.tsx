
import { useEffect } from 'react';

const App = () => {
  useEffect(() => {
    // Redirect to the static HTML version
    window.location.href = '/index.html';
  }, []);

  return null;
};

export default App;
