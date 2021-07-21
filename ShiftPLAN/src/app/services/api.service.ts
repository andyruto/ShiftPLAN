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
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError, from } from 'rxjs';
import { catchError, finalize, tap, switchMap } from 'rxjs/operators';

// marks this service as singelton
@Injectable({
  providedIn: 'root'
})

// this class is responsible for post requests
export class ApiService {

  // variables:
  private URL_BEGINN?: string = undefined;
  private urlEnd: string = '';
  private requestBody: object = {} as object;
  
  public busyCount = 0;
  public get isBusy(): boolean { return this.busyCount > 0; }

  // constructor
  constructor(private httpClient: HttpClient) {
    this.setURLAdress();
  }

  setURLAdress() {
    var urlAdress = localStorage.getItem('URLAdress')
    if(urlAdress) {
      this.URL_BEGINN = urlAdress;
    }
  }

  // method to initiate a post request
  public async sendPostRequest<T>(urlEnd: string, requestBody: object) {
    this.setURLAdress();
    this.urlEnd = urlEnd;
    this.requestBody = requestBody;

    return this.notifyApi<T>();
  }

  // actual api call
  private notifyApi<T>(): Observable<T> {
    const headers = { 'Content-Type': 'application/json' };
    const body = JSON.stringify(this.requestBody);
    return from([0]).pipe(
      tap(() => {
        this.busyCount++;
      }),
      switchMap(() => {
        return this.httpClient.post<T>(this.URL_BEGINN + this.urlEnd, body, {'headers': headers}).pipe(catchError(this.errorHandler));
      })
    )
    .pipe(
        catchError((err: HttpErrorResponse) => {
            return throwError(err)
        }),
        finalize(() => {
          this.busyCount--;
        })
    );
  }

  errorHandler(error: HttpErrorResponse) {
    return throwError(
      error.status);
  }
}