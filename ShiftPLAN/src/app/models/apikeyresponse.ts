/**
 * apikeyresponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-24 / Sascha W.
 */

import { GeneralResponse } from 'src/app/models/generalresponse';

// response interface to receive a response of a apikey post request
export interface ApiKeyResponse extends GeneralResponse  {
  apiKey: string;
  }