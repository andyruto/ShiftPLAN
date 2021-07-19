/**
 * getusersresponse.ts
 * 
 * Interface to receive the response for a post request.
 * 
 * author: Sascha W.
 * last edit / by: 2021-07-01 / Sascha W.
 */

 import { GeneralResponse } from 'src/app/models/generalresponse';

 // response interface to receive a response for the get users post request
export interface GetUsersResponse extends GeneralResponse  {
   profiles: [
         id: number,
         name: string
      ];
}