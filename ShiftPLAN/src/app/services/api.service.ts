/**
 * api.service.ts
 * 
 * Service to do post requests to the api.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-23 / Sascha W.
 */

// imports:
import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

// marks this service as singelton
@Injectable({
  providedIn: 'root'
})

// this class is responsible for post requests
export class ApiService {

  // variables:
  readonly URL_BEGINN: string = 'http://shiftplan.bplaced.net/api/';
  private urlEnd: string = '';
  private requestBody: object = {} as object;

  // constructor
  constructor(private httpClient: HttpClient) { }

  // method to initiate a post request
  public async sendPostRequest<T>(urlEnd: string, requestBody: object) {
    this.urlEnd = urlEnd;
    this.requestBody = requestBody;
    
    return this.notifyApi<T>();
  }

  // actual api call
  private notifyApi<T>(): Observable<T> {
    const headers = { 'Content-Type': 'application/json' };
    const body = JSON.stringify(this.requestBody);
    return this.httpClient.post<T>(this.URL_BEGINN + this.urlEnd, body, {'headers': headers})
  } 
}
