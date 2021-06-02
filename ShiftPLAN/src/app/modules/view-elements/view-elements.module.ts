/**
 * view-elements.module.ts
 * 
 * Module that contains all components that are used multiple times in the application.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ToolbarComponent } from './toolbar/toolbar.component';
import { BottomBarComponent } from './bottom-bar/bottom-bar.component';

@NgModule({
  declarations: [
    ToolbarComponent,
    BottomBarComponent,
  ],
  imports: [
    CommonModule,
  ]
})
export class ViewElementsModule { }
