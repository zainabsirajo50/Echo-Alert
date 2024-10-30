import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from './pages/Home.js';
import './styles/Home.css';
import './App.css';

function App() {
  return (
    <div className="App">
        <Router>
          <Routes>
            <Route path="/" element={<Home />} />  
          </Routes>
        </Router>
    </div>
  );
}

export default App;
