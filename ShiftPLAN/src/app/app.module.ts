/**
 * app.module.ts
 * 
 * Main Angular module of our app importing and bootstrapping
 * all the required elements for our application.
 * 
 * author: Sascha W.
 * last edit / by: 2021-05-23 / Sascha W.
 */

import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';

import { MatToolbarModule } from '@angular/material/toolbar';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatButtonToggleModule } from '@angular/material/button-toggle';
import { MatInputModule } from '@angular/material/input';
import { ToolbarComponent} from '../app/modules/view-elements/toolbar/toolbar.component'
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
    AppComponent,
    ToolbarComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    BrowserAnimationsModule,
    MatToolbarModule,
    MatButtonModule,
    MatIconModule,
    MatButtonToggleModule,
    MatInputModule,
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
