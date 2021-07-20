/**
 * getshiftsresponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-07-20 / Sascha W.
 */

 import { GeneralResponse } from 'src/app/models/generalresponse';

 // response interface to receive a response for the get shifts post request
export interface GetShiftsResponse extends GeneralResponse  {
   shifts: [{
      id: number,
      weekday: string,
      date: string,
      start: string,
      end: string,
      task: string,
      employees: string[]
}];
}