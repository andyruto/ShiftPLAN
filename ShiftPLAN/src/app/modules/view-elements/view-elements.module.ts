/**
 * view-elements.module.ts
 * 
 * Module that contains all components that are used multiple times in the application.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-06-05 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { SharedModule } from '../shared/shared.module'
import { ToolbarComponent } from './toolbar/toolbar.component';
import { BottomBarComponent } from './bottom-bar/bottom-bar.component';
import { BottomNavModule } from 'ngx-bottom-nav';
import { MatToolbarModule } from '@angular/material/toolbar';
import { SpinnerComponent } from './spinner/spinner.component';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

@NgModule({
  declarations: [
    ToolbarComponent,
    BottomBarComponent,
    SpinnerComponent
  ],
  imports: [
    SharedModule,
    BottomNavModule,
    MatToolbarModule,
    MatProgressSpinnerModule
  ],
  exports: [
    ToolbarComponent,
    BottomBarComponent,
    SpinnerComponent
  ]
})
export class ViewElementsModule { }
