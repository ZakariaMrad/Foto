import cors from 'cors';
import express from 'express';

const app = express();

app.use(cors());
app.use(express.json());

app.get('/status', (request, response) => { response.status(200).end(); });
app.head('/status', (request, response) => { response.status(200).end(); });

export default app;