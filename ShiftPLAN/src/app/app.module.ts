/**
 * app.module.ts
 * 
 * Main Angular module of our app importing and bootstrapping
 * all the required elements for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-06-05 / Anne Naumann
 */

import { NgModule } from '@angular/core';
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { SharedModule } from './modules/shared/shared.module';
import { BrowserModule } from '@angular/platform-browser';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { IconService } from './services/icon.service';
import { TranslateModule, TranslateLoader, TranslateService } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { EncryptionService } from './services/encryption.service';

export function translateHttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http, './assets/i18n/', '.json');
}

@NgModule({
  declarations: [
    AppComponent
  ],
  imports: [
    SharedModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    BrowserModule,
    HttpClientModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: translateHttpLoaderFactory,
        deps: [HttpClient]
      }
    })
  ],
  providers: [EncryptionService],
  bootstrap: [AppComponent]
})
export class AppModule {
  availableLng = ['en', 'de'];

  //start the translation service
  constructor(private translateService: TranslateService, private iconService : IconService) {
      //defines the default language
      let tmpLng: string = 'en';
  
      //gets the default browser language
      const currentLng = window.navigator.language.substring(0,2);
  
      if (this.availableLng.includes(currentLng)) {
        tmpLng = currentLng;
      }
  
      this.translateService.setDefaultLang(tmpLng);
      this.iconService.registerIcons()
  }
 }
