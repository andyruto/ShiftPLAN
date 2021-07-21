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
      assignedUser: number,
      supervisorUser: number,
      connectedTaskId: number,
      shiftStart: {
         date: string,
         timezone_type: number,
         timezone: string
      },
      shiftEnd: {
         date: string,
         timezone_type: number,
         timezone: string
      },
      comment: string,
      lastModifiedBy: number,
      lastModified: {
         date: string,
         timezone_type: number,
         timezone: string
      }
   }];
}