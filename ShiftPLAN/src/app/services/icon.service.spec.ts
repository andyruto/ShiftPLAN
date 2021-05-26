/**
 * icon.service.spec
 * 
 * Typescript class containing the tests for the 
 * icon service.
 * 
 * author: Anne Naumann
 * last edit / by: 2021-05-26 / Anne Naumann
 */
import { TestBed } from '@angular/core/testing';
import { IconService } from './icon.service';

describe('IconServiceService', () => {
  let service: IconService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(IconService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
