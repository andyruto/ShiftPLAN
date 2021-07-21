/**
 * gettaskresponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-07-19 / Sascha W.
 */

 import { GeneralResponse } from 'src/app/models/generalresponse';

 // response interface to receive a response for the get task post request
export interface GetTaskResponse extends GeneralResponse  {
   task: [{
      id: number,
      name: string,
      recurring: boolean
}];
}