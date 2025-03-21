
import { useEffect } from 'react';

const App = () => {
  useEffect(() => {
    // توجيه المستخدم إلى النسخة الثابتة HTML
    window.location.href = '/index.html';
  }, []);

  return null;
};

export default App;
